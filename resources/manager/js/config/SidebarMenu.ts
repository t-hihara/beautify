type MenuItem = {
  id: number;
  label: string;
  icon: string;
  route: string;
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
