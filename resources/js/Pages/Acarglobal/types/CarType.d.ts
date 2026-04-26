export type Car = {
  id: number,
  key: string,
  suspension: string,
  type: string,
  // km: number,
  year: string,
  vin: string,
  customer?: string,
  phone?: string,
  address?: string,
  // color: string,
  // price: number,
  // image_path: string,
  // description: string
}

export type CarDetail = {
  car_fix: CarFix[],
} & Car;

export type Activity = {
  id: number,
  car_id: number,
  car_fix_id: number,
  status: string,
  title: string,
  price: number,
  qty: number,
  note?: string,
  product_id?: number,
  created_at: string,
  updated_at: string,
  deleted_at: string,
}

export type CarFix = {
  id: number,
  car: Car,
  activities: Activity[],
  status: string,
  status_label: string,
  created_at: string,
  updated_at: string,
  deleted_at: string,
}