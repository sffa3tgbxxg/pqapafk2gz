import {
  mdiAccountCircle,
  mdiMonitor,
  mdiGithub,
  mdiLock,
  mdiAlertCircle,
  mdiSquareEditOutline,
  mdiTable,
  mdiViewList,
  mdiTelevisionGuide,
  mdiResponsive,
  mdiPalette,
  mdiReact,
} from '@mdi/js'

export default [
  {
    to: '/dashboard',
    icon: mdiMonitor,
    label: 'Статистика',
  },
  {
    to: '/services',
    label: 'Сервисы',
    icon: mdiTable,
  },
  {
    to: '/forms',
    label: 'Формы',
    icon: mdiSquareEditOutline,
  },
  {
    to: '/ui',
    label: 'UI',
    icon: mdiTelevisionGuide,
  },
  {
    to: '/responsive',
    label: 'Отзывчивость',
    icon: mdiResponsive,
  },
  {
    to: '/',
    label: 'Стили',
    icon: mdiPalette,
  },
  {
    to: '/profile',
    label: 'Настройки',
    icon: mdiAccountCircle,
  },
  {
    to: '/login',
    label: 'Вход',
    icon: mdiLock,
  },
  {
    to: '/error',
    label: 'Ошибка',
    icon: mdiAlertCircle,
  },
  {
    label: 'Dropdown',
    icon: mdiViewList,
    menu: [
      {
        label: 'Item One',
      },
      {
        label: 'Item Two',
      },
    ],
  },
]
