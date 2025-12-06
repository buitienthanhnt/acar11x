import { HomeItem } from "./Home.d";

type TypeValue = "one" | "two" | "three" | "four" | "five" | "six" | "all";

export type RoomItem = {
	id: number,
	title: string,
	description: string,
	type: TypeValue,
	home_id: number,
	created_at: string,
}

export type RoomDetail = RoomItem & {
	home: HomeItem,
}