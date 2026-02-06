export type EnumType = {
  id: string | number;
  name: string;
};

export type PaginationLinkType = {
  url: string;
  label: string;
  page: string | number;
  active: boolean;
};

export type PaginationType = {
  currentPage: number;
  lastPage: number;
  prev: string;
  next: string;
  total: number;
};
