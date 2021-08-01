export default [
  {
    icon: 'mdi-view-dashboard',
    title: 'mainPage',
    to: '/home',
  },
  {
    to: '/final-grades',
    icon: 'mdi-view-comfy',
    title: 'final_grades',
  },
  {
    to: '/criteria',
    icon: 'mdi-select-marker',
    title: 'criteria',
  },
  {
    to: '/pharmacies',
    icon: 'mdi-hospital-building',
    title: 'pharmacies',
    group: '',
    children: [
      {
        to: 'pharmacy-rating',
        avatar: 'mdi-clipboard-outline',
        title: 'pharmacyRating',
      },
    ],
  },
  {
    to: '/users',
    icon: 'mdi-account-box',
    title: 'users',
  },
]
