import * as TaxonomyTypes from "./taxonomy.d";
import * as ContentTypes from "./content.d";

type StringProperty = { [key: string]: string };

type DbId = number;

/** The name of this aliase should probably be Templates */
export type Contexts =
  | "not_found"
  | "single"
  | "external"
  | "post_type_archive"
  | "taxonomy_archive"
  | "home";

  /** Describes Context type aliase */
type ContextValues =
  | "post"
  | "page"
  | "category"
  | "post_tag"
  | "nav_menu_item"
  | "custom";

export interface ContentModel {
  id: DbId;
  path: string;
  content: ContentTypes.ContentBody | TaxonomyTypes.TaxDescription | "";
  context: Contexts;
  contextValue: ContextValues;
  title: ContentTypes.ContentTitle | TaxonomyTypes.TaxTitle | "";
}

export interface MenuModel extends ContentModel {
  context: Contexts;
  props: {
    id: DbId;
    target: '_blank' | string;
    classes: Array<string>;
    context: Contexts;
    contextValue: ContextValues;
    breadcrumb: Array<string>;
  };
  children: Array<MenuModel>;
}

type StaticObject = {
  menus: { [key: string]: Array<MenuModel> };
};

type Eckode = {
  nonces: StringProperty;
  rest_bases: StringProperty;
  static: StaticObject;
  boot: ContentModel;
};

declare global {
  interface Window {
    /** Eckode global property */
    Eckode: Eckode;
  }
}
