import { ContentProps, DbId } from ".";

type ContentTitle = string;
type ContentBody = string;

/** Context type aiase */
type Context =
  | "not_found"
  | "single"
  | "external"
  | "post_type_archive"
  | "taxonomy_archive"
  | "home";

/** Describes Context type aliase */
type ContextValue =
  | "post"
  | "page"
  | "category"
  | "post_tag"
  | "nav_menu_item"
  | "custom";

export interface Model {
  id: DbId;
  path: string;
  content: ContentBody | "";
  context: Context;
  context_value: ContextValue;
  title: ContentTitle | "";
  props: ContentProps;
}

export default {};