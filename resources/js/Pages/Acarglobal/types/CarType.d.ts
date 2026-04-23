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

export type CarFix = {
  car_info: Car,
  status: string,
  created_at: string,
  updated_at: string,
  deleted_at: string,
}