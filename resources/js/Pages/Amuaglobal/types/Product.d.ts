export type ProductType = {
  id: number;
  name: string;
  sku: string;
  price: number;
  base_price: number;
  image: string;
  description?: string;
  url?: string;
  qty: number;
  dvt?: string;
}

export type ProductDetailType = ProductType & {
  category: any[];
  created_at: string;
  updated_at: string;
  deleted_at: string;
}