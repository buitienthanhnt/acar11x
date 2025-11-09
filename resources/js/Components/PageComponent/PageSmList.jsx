import { ImagePage } from "../Custom"
import { Link } from "@inertiajs/react"
import { ListItemInfo } from "../Custom/ListItem";

const PageSmList = ({ items, title }) => {
	if (!items) {
		return null;
	}
	
	return (
		<div className="flex lg:space-x-1">
			<div className="space-y-1 bg-white p-1 rounded-md lg:w-3/4">
				<h3 className="font-semibold text-2xl text-blue-700 lg:py-2">{title || 'Ngẫu nhiên:'}</h3>
				{items.map((item, index) => <PageSmItem item={item} key={`sm-${index}`}></PageSmItem>)}
			</div>
			<div className="bg-white invisible lg:visible w-0 lg:w-1/4 p-0 lg:p-1 lg:py-2 rounded-md flex-row justify-center">
				<p className="justify-center flex content-center">Quảng cáo!</p>
				{/* <ImagePage source={"http://adoc.dev/storage/photos/shares/global/vu-thuy-quynh-4-0-1726370672.jpg"} className='h-full object-center'></ImagePage> */}
			</div>
		</div>
	)
}

const PageSmItem = ({ item }) => {
	return (
		<Link className="flex p-1 bg-gray-200 rounded-md" href={route('detail', { alias: item.alias })}>
			<div className="bg-gray-400 rounded-md justify-center items-center flex">
				<ImagePage source={item.image_path} className={'w-28 md:w-[180px] h-full md:h-auto rounded-md max-w-none'}></ImagePage>
			</div>
			<div className="px-2 space-y-1 w-full grid">
				<div className="">
					<p className="font-bold text-black">{item?.title}</p>
					<p dangerouslySetInnerHTML={{ __html: item?.desciption }} className="line-clamp-2 text-gray-700"></p>
				</div>
				<ListItemInfo source={item?.source} updated_at={item.updated_at} comments_count={item.comments_count} className={'space-y-1'}></ListItemInfo>
			</div>
		</Link>
	)
}

export { PageSmList, PageSmItem }