import { FunctionComponent } from "react";
import { RoomHome } from "../types/Room"
import { Link } from "@inertiajs/react";
import { sprintf } from "sprintf-js";
import Urls from "../netWork/Urls";
import { CubeIcon, CurrencyDollarIcon, MapPinIcon, UsersIcon } from "@heroicons/react/24/solid";

type Props = {
	room: RoomHome;
	selectedDates?: any;
}

const RoomItemGrid: FunctionComponent<Props> = ({ room, selectedDates }) => {
	return (
		<Link href={sprintf(Urls.homeDetail, [room.home_id])} data={{ room: room.id, selectedDates: selectedDates }}
			className="shadow-sm md:shadow-md hover:shadow-lg rounded-md gap-x-1 md:gap-x-4 flex md:flex-col 
			bg-gradient-to-r from-blue-gray-200 to-blue-gray-100 md:bg-transparent md:from-transparent md:to-transparent h-full "
		>
			<img src={room.image_path} alt="room avata" className="max-w-40 aspect-video md:max-w-60 lg:max-w-full md:min-h-44 lg:min-h-56 h-full rounded-md md:rounded-t-md md:rounded-b-none" />
			<div className="flex flex-col justify-between md:justify-start md:p-1 h-full space-y-1 py-1 md:py-2">
				<div className="flex flex-col justify-between md:justify-start md:p-1 h-full space-y-1">
					<p className='font-semibold text-purple-500'>Phòng: {room.title}</p>
					<div className='flex items-center gap-x-1'>
						<CubeIcon className='size-5 text-gray-800'></CubeIcon>
						<p className='text-black font-semibold text-sm md:text-md'>{room.description}</p>
					</div>
					<div className='flex items-center gap-x-1'>
						<CurrencyDollarIcon className='size-5 text-gray-800'></CurrencyDollarIcon>
						<p className='text-black font-semibold text-sm md:text-md'>{room.price} 000 VND</p>
					</div>
					<div className='flex items-center gap-x-1'>
						<UsersIcon className='size-5 text-black'></UsersIcon>
						<p className='font-semibold text-sm md:text-md'>{room.type}</p>
					</div>
				</div>
				<div className='flex items-center gap-x-1'>
					<MapPinIcon className='size-5 text-black'></MapPinIcon>
					<p className='font-semibold text-sm md:text-md'>{room.home.district}</p>
				</div>
			</div>
		</Link>
	)
}

export default RoomItemGrid;