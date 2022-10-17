import { Model } from "./content";
import * as TaxonomyTypes from "./taxonomy.d";

type ContentBody = string;

type StringProperty = Record<string, string>;

type DbId = number;

/** Endpoint appended to rest requests url */
export type Endpoint = string;

/** The name of this aliase should probably be Templates */
export type Contexts =
  | "not_found"
  | "single"
  | "external"
  | "post_type_archive"
  | "taxonomy_archive"
  | "home";

/** Describes Context type aliase */
export type ContextValues =
  | "post"
  | "page"
  | "product"
  | "entry"
  | "category"
  | "post_tag"
  | "nav_menu_item"
  | "custom";

type MenuLocations = "main" | "footer";

export interface ContentProps {
  id: DbId;
  context: Contexts;
  context_value: ContextValues;
  breadcrumb: Array<string>;
  // Optional
  excerpt?: string;
  target?: "_blank" | string;
  classes?: Array<string>;
}

export interface ContentModel extends Model {
  id: DbId;
  path: string;
  content: ContentBody | TaxonomyTypes.TaxDescription | "";
  context: Contexts;
  context_value: ContextValues;
  title: ContentTitle | TaxonomyTypes.TaxTitle | "";
  props: ContentProps & Partial<Record<ContextValues, Array<ContentModel>>>;
}

export interface MenuModel extends ContentModel {
  children: Array<MenuModel>;
}

type StaticObject = {
  menus: { [K in MenuLocations]?: Array<MenuModel> };
};

type Eckode = {
  nonces: StringProperty;
  rest_bases: Record<"post_type" | "taxonomy", Record<ContextValues, string>>;
  static: StaticObject;
  boot: ContentModel;
  config: {
    endpoint: Endpoint;
    nonces: StringProperty;
    posts_per_page: number;
  };
};

declare global {
  interface Window {
    /** Eckode global property */
    Eckode: Eckode;
  }
}
