import { FunctionComponent } from "react";
import { HomeItem as HomeItemType } from "../types/Home.d";
import { Link } from "@inertiajs/react";
import { sprintf } from "sprintf-js";
import Urls from "../netWork/Urls";
import { RoomItem as RoomItemType } from "../types/Room";
import { RoomItemGrid } from "../Components";

const HomeItem = ({ home }: { home: HomeItemType }) => {
	return (
		<Link className="bg-green-200 shadow-md p-2 rounded-md" href={sprintf(Urls.homeDetail, [home.id])}>
			<div>
				<img src={home.image_path} alt="avata hotel" className="rounded-md" />
			</div>
			<div className="mt-2 flex flex-col gap-y-2">
				<h4 className="text-2xl text-blue-700 font-bold ">{home.name}</h4>
				<p className="text-xl font-semibold">{home.description}</p>
				<p className="italic text-purple-600 text-end font-semibold">{home.district}</p>
			</div>
		</Link>
	)
}

type Props = {
	homes: HomeItemType[],
	rooms: RoomItemType[],
}

const HomeList: FunctionComponent<Props> = ({ homes, rooms }) => {

	return (
		<div>
			<div className="p-4 gap-2">
				<h3 className="text-lg font-semibold text-purple-300">List of home register:</h3>
				<div className="p-4 grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-3">
					{homes.map(home => <HomeItem home={home} key={home.id.toString()}></HomeItem>)}
				</div>
			</div>

			<div className="p-4 gap-2">
				<h3 className="text-lg font-semibold text-purple-300">List of rooms:</h3>
				<div>
					<span className="font-medium text-lg underline">filter by date, location, type, price!!</span>
				</div>
				<div className="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
					{rooms.map(room => <div>
						<RoomItemGrid room={room}></RoomItemGrid>
					</div>)}
				</div>
			</div>
		</div>
	)
}

export default HomeList;