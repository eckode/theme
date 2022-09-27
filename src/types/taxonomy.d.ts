type TaxId = number;
type TaxTitle = string;
type TaxSlug = string;
type TaxDescription = string;
type TaxName = string;

export interface Model {
  slug: TaxSlug;
  parent: TaxId;
  count: number;
  term_id: TaxId;
  name: TaxTitle;
  taxonomy: TaxName;
  term_group: number;
  filter: "raw" | string;
  term_taxonomy_id: number;
  description: TaxDescription;
}

// Category specific properties
export interface CategoryModel extends Model {
  cat_ID: TaxId;
  cat_name: TaxTitle;
  taxonomy: "category";
  category_count: number;
  category_parent: TaxId;
  category_nicename: TaxSlug;
  category_description: TaxDescription;
}
