export default [
  {
    icon: 'mdi-view-dashboard',
    title: 'mainPage',
    to: '/home',
  },
  {
    icon: 'mdi-account-multiple',
    title: 'staff',
    group: '',
    children: [
      {
        to: 'staff',
        avatar: 'mdi-view-comfy',
        title: 'Пользователи',
      },
      {
        to: 'create-staff',
        avatar: 'mdi-clipboard-outline',
        title: 'Создать пользователя',
      },
    ],
  },
]
