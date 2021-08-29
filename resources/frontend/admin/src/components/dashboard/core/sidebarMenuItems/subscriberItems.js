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
]
