import { FunctionComponent } from "react";
import { RoomItem } from "../../types/Room";
import { CubeIcon, CurrencyDollarIcon, UsersIcon } from "@heroicons/react/24/solid";

type Props = {
	room: RoomItem;
}

const RoomInfo: FunctionComponent<Props> = ({ room }) => {
	return (
		<div className="bg-gray-400 p-1 md:p-2 rounded-md">
			{/* <h3 className="text-md md:text-lg font-semibold my-1">Thông tin:</h3> */}
			{room ? <div>
				<p className='font-semibold text-purple-500'>Phòng: {room.title}</p>
				<div className='flex flex-col gap-1'>
					<div className='flex items-center gap-x-2'>
						<CubeIcon className='size-5 text-gray-800'></CubeIcon>
						<p className='text-black font-semibold text-sm md:text-md'>{room.description}</p>
					</div>
					<div className='flex items-center gap-x-2'>
						<UsersIcon className='size-5 text-black'></UsersIcon>
						<p className='font-semibold text-sm md:text-md'>{room.type}</p>
					</div>
					<div className='flex items-center gap-x-2'>
						<CurrencyDollarIcon className='size-5 text-gray-800'></CurrencyDollarIcon>
						<p className='text-black font-semibold text-sm md:text-md'>{new Intl.NumberFormat('de-DE', {
							style: 'currency',
							currency: 'VND',
						}).format(room.price * 1000)}</p>
					</div>
				</div>
			</div> : <p className="text-green-700">
				Chọn phòng ngẫu nhiên
			</p>}
		</div>
	)
}

export default RoomInfo;