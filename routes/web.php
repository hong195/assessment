<?php

use App\Notifications\FinalGradeCompleted;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Model\Employee\EmployeeRepository;
use App\Domain\Model\Pharmacy\PharmacyId;
use App\Domain\Model\User\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Ramsey\Uuid\Uuid;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('admin');
});

Route::get('rating-import', function () {
    $ratings = DB::connection('import')->table('ratings')->where('id', '=', 100)->get();

    $ratings->each(function ($rating) {
        $finalGradeId = Uuid::uuid4()->toString();
        $employee = DB::table('employees')
            ->where('import_id', $rating->user_id)
            ->first();

        $checksIds = DB::connection('import')
            ->table('check_rating')
            ->where('rating_id', $rating->id)
            ->get()
            ->map->check_id
            ->toArray();

        $checks = DB::connection('import')
            ->table('checks')
            ->whereIn('id', $checksIds)
            ->get();

        DB::table('final_grades')->insert([
            'id' => $finalGradeId,
            'employee_id' => $employee->id,
            'scored' => $rating->scored,
            'total' => $rating->out_of,
            'status_status' => 'completed',
            'month_date' => new \DateTime($rating->created_at)
        ]);

        $checks->each(function ($check) use ($finalGradeId) {
            $reviewer = DB::table('users')
                ->where('import_id', $check->reviewer_id)
                ->first();

            $name = $reviewer ? "$reviewer->last_name $reviewer->first_name $reviewer->patronymic" : "Неизвестно Неизвестно Неизвестно";
            $assessmentId = Uuid::uuid4()->toString();

            $criteria = collect(array_values(json_decode($check->criteria, true)))
                ->filter(function ($criterion) {
                    return isset($criterion['options']);
                });

            $criteria = collect($criteria->values())->map(function ($criterion) {
                    if (empty($criterion['options'])) {
                        return [];
                    }
                    $options = collect($criterion['options']);

                    $selected = $options
                        ->filter(fn($option) => $option['selected'])->first();
                    $maxPoint = $options->max(fn($option) => $option['value']);

                    return [
                        "name" => $criterion['name'],
                        "label" => $criterion['label'],
                        "options" => $options->map(fn($option) => [
                            'name' => $option['label'],
                            'value' => $option['value'],
                            'description' => $option['description']
                        ])->toArray(),
                        'maxPoint' => $maxPoint,
                        "selected" => $selected['label'],
                        "description" => "",
                        "selectedValue" => $selected['value'],
                    ];
                })->toArray();

            DB::table('assessments')->insert([
                'id' => $assessmentId,
                'analysis_id' => $finalGradeId,
                'check_amount' => $check->sum,
                'check_sale_conversion' => $check->conversion,
                'check_service_date' => new \DateTime($check->created_at),
                'reviewer_name' => $name,
                'reviewer_reviewer_id' => $reviewer ? $reviewer->id : 1,
                'criteria' => json_encode($criteria),
            ]);
        });
    });
});


Route::get('import', function () {

    $pharmacies = DB::connection('import')
        ->table('pharmacies')
        ->get();

    $pharmacies->each(function ($el) {
        $pharmacyId = Uuid::uuid4()->toString();

        DB::table('pharmacies')->insert([
            'id' => $pharmacyId,
            'email_email' => $el->email,
            'address' => $el->address,
            'number_number' => $el->name
        ]);

        $users = DB::connection('import')
            ->table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('pharmacy_id', $el->id)
            ->select('users.*', 'model_has_roles.*', 'roles.name as role')
            ->get();

        $users->each(function ($user) use ($pharmacyId) {
            $userData = DB::connection('import')
                ->table('user_data')
                ->where('user_id', $user->id)
                ->get();


            if ($user->role_id == 1 || $user->role_id == 3) {
                DB::table('users')->insert([
                    'name_first' => "$user->first_name",
                    'name_middle' => "$user->last_name",
                    'name_last' => "$user->patronymic",
                    'login_login' => $user->login,
                    'role_role' => strtolower($user->role),
                    'password' => $user->password,
                    'import_id' => $user->id
                ]);
            } else {
                $gender = collect($userData)->filter(function ($data) {
                    return $data->name == 'gender';
                })->first();
                $gender = $gender ? trim(jdecoder($gender->value), '"') : 'male';
                $gender = $gender === 'Женский' ? 'female' : 'male';

                $birthday = collect($userData)->filter(function ($data) {
                    return $data->name == 'birthday';
                })->first();

                $birthday = trim($birthday->value, '"') ?? now()->toDateString();

                DB::table('employees')->insert([
                    'id' => Uuid::uuid4()->toString(),
                    'pharmacy_id' => $pharmacyId,
                    'name_first_name' => "$user->first_name",
                    'name_middle' => "$user->last_name",
                    'name_last_name' => "$user->patronymic",
                    'gender_gender' => $gender,
                    'birthdate' => new \DateTime($birthday),
                    'import_id' => $user->id
                ]);
            }
        });
    });
});

function jdecoder($json_str)
{
    $cyr_chars = array(
        '\u0430' => 'а', '\u0410' => 'А',
        '\u0431' => 'б', '\u0411' => 'Б',
        '\u0432' => 'в', '\u0412' => 'В',
        '\u0433' => 'г', '\u0413' => 'Г',
        '\u0434' => 'д', '\u0414' => 'Д',
        '\u0435' => 'е', '\u0415' => 'Е',
        '\u0451' => 'ё', '\u0401' => 'Ё',
        '\u0436' => 'ж', '\u0416' => 'Ж',
        '\u0437' => 'з', '\u0417' => 'З',
        '\u0438' => 'и', '\u0418' => 'И',
        '\u0439' => 'й', '\u0419' => 'Й',
        '\u043a' => 'к', '\u041a' => 'К',
        '\u043b' => 'л', '\u041b' => 'Л',
        '\u043c' => 'м', '\u041c' => 'М',
        '\u043d' => 'н', '\u041d' => 'Н',
        '\u043e' => 'о', '\u041e' => 'О',
        '\u043f' => 'п', '\u041f' => 'П',
        '\u0440' => 'р', '\u0420' => 'Р',
        '\u0441' => 'с', '\u0421' => 'С',
        '\u0442' => 'т', '\u0422' => 'Т',
        '\u0443' => 'у', '\u0423' => 'У',
        '\u0444' => 'ф', '\u0424' => 'Ф',
        '\u0445' => 'х', '\u0425' => 'Х',
        '\u0446' => 'ц', '\u0426' => 'Ц',
        '\u0447' => 'ч', '\u0427' => 'Ч',
        '\u0448' => 'ш', '\u0428' => 'Ш',
        '\u0449' => 'щ', '\u0429' => 'Щ',
        '\u044a' => 'ъ', '\u042a' => 'Ъ',
        '\u044b' => 'ы', '\u042b' => 'Ы',
        '\u044c' => 'ь', '\u042c' => 'Ь',
        '\u044d' => 'э', '\u042d' => 'Э',
        '\u044e' => 'ю', '\u042e' => 'Ю',
        '\u044f' => 'я', '\u042f' => 'Я',

        '\r' => '',
        '\n' => '<br />',
        '\t' => ''
    );

    foreach ($cyr_chars as $key => $value) {
        $json_str = str_replace($key, $value, $json_str);
    }
    return $json_str;
}
