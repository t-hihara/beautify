type MenuItem = {
  id: number;
  label: string;
  icon: string;
  route?: string;
  children: SubMenuItem[];
};

type SubMenuItem = {
  id: number;
  label: string;
  route: string;
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
      },
      {
        id: 2,
        label: "店舗登録",
        route: "admin.shops.index",
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
];
