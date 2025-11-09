import {
	Timeline,
	TimelineItem,
	TimelineConnector,
	TimelineHeader,
	TimelineIcon,
	TimelineBody,
	Typography,
} from "@material-tailwind/react";
import { HomeIcon, BellIcon, CurrencyDollarIcon } from "@heroicons/react/24/solid";


const DupList = ({ items }) => {
	return (
		<div className="bg-white p-4 grid grid-cols-3 gap-2">
			<div className="col-span-2">
				<Timeline>
					<TimelineItem>
						<TimelineConnector />
						<TimelineHeader>
							<TimelineIcon className="p-2">
								<HomeIcon className="h-4 w-4" />
							</TimelineIcon>
							<Typography variant="h5" color="blue-gray">
								Timeline Title Here.
							</Typography>
						</TimelineHeader>
						<TimelineBody className="pb-8">
							<Typography color="gary" className="font-normal text-gray-600">
								The key to more success is to have a lot of pillows. Put it this way, it took me
								twenty five years to get these plants, twenty five years of blood sweat and tears, and
								I&apos;m never giving up, I&apos;m just getting started. I&apos;m up to something. Fan
								luv.
							</Typography>
						</TimelineBody>
					</TimelineItem>
					<TimelineItem>
						<TimelineConnector />
						<TimelineHeader>
							<TimelineIcon className="p-2">
								<BellIcon className="h-4 w-4" />
							</TimelineIcon>
							<Typography variant="h5" color="blue-gray">
								Timeline Title Here.
							</Typography>
						</TimelineHeader>
						<TimelineBody className="pb-8">
							<Typography color="gary" className="font-normal text-gray-600">
								The key to more success is to have a lot of pillows. Put it this way, it took me
								twenty five years to get these plants, twenty five years of blood sweat and tears, and
								I&apos;m never giving up, I&apos;m just getting started. I&apos;m up to something. Fan
								luv.
							</Typography>
						</TimelineBody>
					</TimelineItem>
					<TimelineItem>
						<TimelineHeader>
							<TimelineIcon className="p-2">
								<CurrencyDollarIcon className="h-4 w-4" />
							</TimelineIcon>
							<Typography variant="h5" color="blue-gray">
								Timeline Title Here.
							</Typography>
						</TimelineHeader>
						<TimelineBody>
							<Typography color="gary" className="font-normal text-gray-600">
								The key to more success is to have a lot of pillows. Put it this way, it took me
								twenty five years to get these plants, twenty five years of blood sweat and tears, and
								I&apos;m never giving up, I&apos;m just getting started. I&apos;m up to something. Fan
								luv.
							</Typography>
						</TimelineBody>
					</TimelineItem>
				</Timeline>
			</div>
			<div className="col-span-1 bg-blue-gray-100 invisible md:visible rounded-md flex justify-center p-1">
				<span className="">Quảng cáo!</span>
			</div>
		</div>
	);
	return (
		<div className="bg-white grid grid-cols-2 p-1 gap-1 rounded-md">
			{items.map((item, index) => <DupListItem item={item} key={index}></DupListItem>)}
		</div>
	)
}

const DupListItem = ({ item }) => {
	return (
		<div className="flex justify-center items-center bg-gray-200">
			<ImagePage source={item.image_path} className="w-full rounded-md"></ImagePage>
			{/* <p>{item.title}</p> */}
		</div>
	)
}

export { DupList, DupListItem };