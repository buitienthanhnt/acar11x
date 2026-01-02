import { FunctionComponent } from "react";
import { HomeDetail } from "../../types/Home";
import { MapPinIcon, SparklesIcon } from "@heroicons/react/24/solid";
import { Rating } from "@material-tailwind/react";

type Props = {
	home: HomeDetail
};
const HomeInfo: FunctionComponent<Props> = ({ home }) => {
	const rate = home?.attr.find(i => i.key === 'rate');

	return (
		<div className="bg-gray-400 p-1 md:p-2 rounded-md">
			<h3 className="text-md md:text-lg font-semibold my-1">Nơi lưu trú:</h3>
			<div>
				<p className='text-md font-bold text-black'>Khách sạn: {home.name}</p>
				<div className='flex space-x-1'>
					<MapPinIcon className="size-5 text-gray-800"></MapPinIcon>
					<p className='text-md font-semibold '>Địa chỉ: {home.district}</p>
				</div>
			</div>
		</div>
	)
}

export default HomeInfo;