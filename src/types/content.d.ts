type ContentId = number;
type ContentTitle = string;
type ContentSlug = string;
type ContentBody = string;
type ContentName = string;

type StringToggle = "open" | "closed";

// @TODO cleanout the ones that are dropped.
export interface Model {
  ID: ContentId;
  post_author: string;
  post_date: Date;
  post_date_gmt: Date;
  post_content: ContentBody;
  post_title: ContentTitle;
  post_excerpt: string;
  post_status:
    | "publish"
    | "future"
    | "draft"
    | "pending"
    | "private"
    | "trash"
    | "auto-draft"
    | "inherit";
  comment_status: StringToggle;
  ping_status: StringToggle;
  post_password: string;
  post_name: ContentName;
  to_ping: string;
  pinged: string;
  post_modified: Date;
  post_modified_gmt: Date;
  post_content_filtered: string;
  post_parent: ContentId;
  guid: string;
  menu_order: number;
  post_type: string;
  post_mime_type: string;
  comment_count: string;
  filter: string;
}
