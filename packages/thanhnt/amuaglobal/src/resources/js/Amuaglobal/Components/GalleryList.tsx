import { Link } from "@inertiajs/react";
import {
	Tabs,
	TabsHeader,
	TabsBody,
	Tab,
	TabPanel,
} from "@material-tailwind/react";

const GalleryList = ({ data }) => {

	return (
		<Tabs value={data[0].alias} className={'bg-white p-1'}>
			<TabsHeader className="bg-gray-100">
				{data.map(({ name, alias }) => (
					<Tab key={alias} value={alias}>
						{name}
					</Tab>
				))}
			</TabsHeader>
			<TabsBody className="grid grid-cols-1 gap-4 ">
				{data.map(({ pages, alias }) => (
					<TabPanel
						className="grid grid-cols-2 gap-4 md:grid-cols-3"
						key={alias}
						value={alias}
					>
						{pages?.map(({ image_path, title, alias}, index) => (
							<Link key={index} href={route('detail', {alias: alias})}>
								<img
									className="h-40 w-full max-w-full rounded-lg object-cover object-center"
									src={image_path}
									alt="image-photo"
								/>
								<p className="line-clamp-2 font-semibold mt-1">{title}</p>
							</Link>
						))}
					</TabPanel>
				))}
			</TabsBody>
		</Tabs>
	);
}

export default GalleryList;