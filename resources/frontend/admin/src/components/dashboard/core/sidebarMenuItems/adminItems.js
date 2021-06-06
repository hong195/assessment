export default [
    {
      icon: 'mdi-view-dashboard',
      title: 'mainPage',
      to: '/home',
    },
    {
      icon: 'mdi-animation',
      title: 'attributes',
      group: '',
      children: [
        {
          to: 'attributes',
          title: 'attributes',
        },
        {
          to: 'create-attributes',
          title: 'create-attributes',
        },
        {
          to: 'attribute-options',
          title: 'attribute-options',
        },
        {
          to: 'create-attribute-options',
          title: 'create-attribute-options',
        },
      ],
    },
    {
      icon: 'mdi-arrange-bring-forward',
      title: 'pharmacy',
      group: '',
      children: [
        {
          to: 'pharmacy',
          avatar: 'mdi-view-comfy',
          title: 'pharmacy',
        },
        {
          to: 'create-pharmacy',
          avatar: 'mdi-clipboard-outline',
          title: 'createPharmacy',
        },
        {
          to: 'pharmacy-rating',
          avatar: 'mdi-clipboard-outline',
          title: 'pharmacyRating',
        },
      ],
    },
    {
      icon: 'mdi-account-multiple',
      title: 'staff',
      group: '',
      children: [
        {
          to: 'staff',
          avatar: 'mdi-view-comfy',
          title: 'staff',
        },
        {
          to: 'create-staff',
          avatar: 'mdi-clipboard-outline',
          title: 'createStaff',
        },
        {
          to: 'ratings-staff',
          icon: 'mdi-view-comfy',
          title: 'ratings',
        },
      ],
    },
  ]
