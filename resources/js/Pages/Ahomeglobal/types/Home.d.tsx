import { RoomItem } from "./Room"

export type HomeItem= {
	id: number,
	name: string,
	description?: string,
	district?: string,

}

export type HomeDetail = HomeItem & {
	rooms: RoomItem[],
}