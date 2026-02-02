import { AttrInterface } from "./Attr"
import { OrderTimeInterface } from "./OrderTime"
import { RoomItem } from "./Room"

export type HomeItemType = {
	id: number,
	name: string,
	description?: string,
	district?: string,
	image_path?: string,
}

export type HomeDetail = HomeItemType & {
	rooms: RoomItem[],
	order_times: OrderTimeInterface[],
	attr: AttrInterface[],
	gallery?: {
		path: string
	}[],
}