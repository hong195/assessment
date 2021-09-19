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
    to: '/sale-managers',
    icon: 'mdi-point-of-sale',
    title: 'sale-manager',
  },
  {
    to: '/pharmacies',
    icon: 'mdi-hospital-building',
    title: 'pharmacies',
    group: '',
    children: [
      {
        to: 'pharmacies',
        avatar: 'mdi-clipboard-outline',
        title: 'pharmacies',
      },
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
