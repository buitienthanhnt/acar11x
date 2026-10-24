export interface CategoryType {
  id: number;
  name: string;
  alias: string;
  description?: string;
  image?: string;
  active: boolean;
  created_at?: string;
  updated_at?: string;
  deleted_at?: string;
  parent_id?: number;
}

export interface CategoryDetailType extends CategoryType {
  childrents?: CategoryType[];
}