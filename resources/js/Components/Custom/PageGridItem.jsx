import { Link } from "@inertiajs/react";
import ImagePage from "./ImagePage";

const PageGrid = ({ data }) => {
	if (!data) {
		return null;
	}

	return (
		<div className="bg-white p-2 rounded-md grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
			{data.map((item, index) => <PageGridItem page={item} key={index}></PageGridItem>)}
		</div>
	)
}

const PageGridItem = ({ page }) => {
	return (
		<Link href={route('detail', { alias: page.alias })} className="col-span-1  rounded-md border-2 border-solid border-violet-500 space-y-2">
			<ImagePage source={page.image_path} className='rounded-t-md h-60 md:h-[180px] object-cover w-full'></ImagePage>
			<p className="line-clamp-2 min-h-9 font-medium px-2">{page.title}</p>
		</Link>
	)
}

export { PageGridItem, PageGrid };