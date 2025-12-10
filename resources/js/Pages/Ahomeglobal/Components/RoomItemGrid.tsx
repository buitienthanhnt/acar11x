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
		>
			<div className="p-1 bg-blue-200 rounded-md shadow-lg min-h-10 lg:p-2">
				<p className="font-bold">
					{room.title}
				</p>
				<p className="font-semibold text-gray-800 text-md">{room.type}</p>
				<p className="font-semibold">price: 100$</p>
				<p>Location:</p>
			</div>

		</Link>
	)
}

export default RoomItemGrid;