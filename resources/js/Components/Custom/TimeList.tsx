import {
	Timeline,
	TimelineItem,
	TimelineConnector,
	TimelineHeader,
	TimelineIcon,
	TimelineBody,
	Typography,
} from "@material-tailwind/react";
import { BellIcon } from "@heroicons/react/24/solid";
import { Link, usePage, WhenVisible } from "@inertiajs/react";
import { stringConvert } from "@/Helper/DateTime";
import { Loading } from "../Skeleton";


const TimeList = ({ name, data }) => {

	return (
		<div className="bg-white p-4 space-y-4 shadow-lg">
			<p className="font-semibold text-black text-2xl">Sắp diễn ra:</p>
			<div className="grid grid-cols-3 gap-2">
				<div className="col-span-3 md:col-span-2">
					<Timeline>
						{data.map((item, index) => <DupListItem item={item} key={index} isComplete={index === data.length - 1}></DupListItem>)}
					</Timeline>
				</div>
				<div className="col-span-1 bg-blue-gray-100 invisible md:visible rounded-md flex justify-center p-1">
					<span className="">Quảng cáo!</span>
				</div>
			</div>
		</div>
	);
}

const DupListItem = ({ item, isComplete }) => {
	const { timeValue } = JSON.parse(item.page_contents[0].value);
	return (
		<Link href={route('detail', { alias: item.alias })}>
			<TimelineItem>
				{!isComplete && <TimelineConnector />}
				<TimelineHeader>
					<TimelineIcon className="p-2">
						<BellIcon className="h-4 w-4" />
					</TimelineIcon>
					<Typography variant="h5" color="blue-gray">
						{item.title}
					</Typography>
				</TimelineHeader>
				{item?.desciption && <TimelineBody className={`pb-0`}>
					<Typography color="gray" className="font-normal text-gray-600">
						{item.desciption}
					</Typography>
				</TimelineBody>}
				<Typography variant="small" color="orange" className="font-normal justify-end flex w-full content-end pb-4">
					{stringConvert(timeValue)}
				</Typography>
			</TimelineItem>
		</Link>
	)
}

export default TimeList;