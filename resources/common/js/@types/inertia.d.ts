import "@inertiajs/core";

declare module "@inertiajs/core" {
  export interface PageProps {
    guard: "admin" | "shop" | "user" | "web";
    auth: {
      user: User | null;
    };
    flash: {
      success: string | null;
      error: string | null;
      warning: string | null;
    };
    japaneseHolidays: string[];
    unloadedExportFileCount: number;
  }
}

export type User = {
  id: number;
  name: string;
};
