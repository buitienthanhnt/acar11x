export type StatusValue = 'complete' | 'success' | 'cancel';

interface CustomerInfoInterface {
  email: string;
  name: string;
  phone: string;
}

interface ShippingAddressInterface {
  location: string;
  name: string;
  phone: string;
}
export interface ExpectOrderInterface {
  id: string;
  status: StatusValue;
  date_from: string;
  date_to: string;
  selected_time: string[];
  item_id: number;
  qty: number;
  total_price: number;
  payment_method: string;
  shipping_method: string;
  shipping_address: ShippingAddressInterface;
  customer_info: CustomerInfoInterface;
}

export interface OrderInterface extends Omit<ExpectOrderInterface, 'id'> {
  id: number;
  increment_id: string;
  created_at: string;
  updated_at: string | null;
  deleted_at: string | null;
}