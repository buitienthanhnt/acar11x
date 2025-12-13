import { FunctionComponent } from "react";
import { RoomItem } from "../types/Room"
import { Link } from "@inertiajs/react";
import { sprintf } from "sprintf-js";
import Urls from "../netWork/Urls";

type Props = {
	room: RoomItem;
}

const RoomItemGrid: FunctionComponent<Props> = ({ room }) => {

	return (
		<Link href={sprintf(Urls.homeDetail, [room.home_id])} data={{ room: room.id }}
			className="p-1 bg-blue-200 rounded-md shadow-lg min-h-10 lg:p-2 flex gap-x-2"
		>
			<div>
				<img src={room.image_path} alt="room avata" className="w-48 min-w-40 aspect-square rounded-md" />
			</div>
			<div >
				<p className="font-bold text-xl">
					{room.title}
				</p>
				<p className="font-semibold text-white text-md">{room.type}</p>
				<p className="font-semibold text-lg text-purple-500">price: {room.price}$</p>
				<p className="text-md font-medium">Description: {room.description}</p>
			</div>
		</Link>
	)
}

export default RoomItemGrid;