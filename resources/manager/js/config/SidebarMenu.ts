type MenuItem = {
  id: number;
  label: string;
  icon: string;
  route?: string;
  permission?: string;
  children: SubMenuItem[];
};

type SubMenuItem = {
  id: number;
  label: string;
  route: string;
  permission?: string;
};

export const adminMenu: MenuItem[] = [
  {
    id: 1,
    label: "ダッシュボード",
    icon: "dashboard",
    route: "admin.dashboard",
    children: [],
  },
  {
    id: 2,
    label: "店舗管理",
    icon: "shop",
    children: [
      {
        id: 1,
        label: "店舗一覧",
        route: "admin.shops.index",
        permission: "view.shops",
      },
      {
        id: 2,
        label: "店舗登録",
        route: "admin.shops.create",
        permission: "manage.shops",
      },
      {
        id: 3,
        label: "店舗スタッフ一覧",
        route: "admin.staffs.index",
        permission: "view.staffs",
      },
      {
        id: 4,
        label: "店舗スタッフ登録",
        route: "admin.staffs.create",
        permission: "manage.staffs",
      },
    ],
  },
  {
    id: 3,
    label: "メニュー管理",
    icon: "menu",
    children: [
      {
        id: 1,
        label: "メニュー一覧",
        route: "admin.menus.index",
      },
      {
        id: 2,
        label: "メニュー登録",
        route: "admin.menus.create",
        permission: "manage.menus",
      },
    ],
  },
  {
    id: 98,
    label: "出力ファイル管理",
    icon: "export",
    route: "admin.exports.index",
    children: [],
  },
  {
    id: 99,
    label: "ログ管理",
    icon: "log",
    route: "admin.logs.index",
    children: [],
  },
];

export const shopMenu: MenuItem[] = [
  {
    id: 1,
    label: "ダッシュボード",
    icon: "dashboard",
    route: "shop.dashboard",
    children: [],
  },
  {
    id: 2,
    label: "店舗管理",
    icon: "shop",
    children: [
      {
        id: 1,
        label: "店舗情報",
        route: "shop.index",
        permission: "view.shops",
      },
      {
        id: 2,
        label: "店舗スタッフ一覧",
        route: "shop.staffs.index",
        permission: "view.staffs",
      },
      {
        id: 3,
        label: "店舋スタッフ登録",
        route: "shop.staffs.create",
        permission: "manage.staffs",
      },
    ],
  },
  {
    id: 3,
    label: "メニュー管理",
    icon: "menu",
    children: [
      {
        id: 1,
        label: "メニュー一覧",
        route: "shop.menus.index",
      },
      {
        id: 2,
        label: "メニュー登録",
        route: "shop.menus.create",
        permission: "manage.menus",
      },
    ],
  },
];
