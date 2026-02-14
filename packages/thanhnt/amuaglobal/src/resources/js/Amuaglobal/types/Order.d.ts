import { HomeItem } from "./Home.d";
import { RoomItem } from "./Room";

export type StatusValue = 'complete' | 'success' | 'cancel';


export interface OrderItemInterface {
	id: number;
	status: StatusValue;
	date_from: string;
	date_to: string;
	selected_time: string[];
	increment_id: string;
};

interface DetailInterface {
	name: string;
	email: string;
	phone: string;
	payment_method: string;
	price: number;
	total_price: number;
	currency: string;
	created_at: string;
	quantity?: number;
}

export interface OrderDetailInterface extends OrderItemInterface {
	room: RoomItem;
	home: HomeItem;
	detail: DetailInterface;
}