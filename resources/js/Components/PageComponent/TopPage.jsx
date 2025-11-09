import { Link,} from "@inertiajs/react"
import { ImagePage } from "../Custom"

// http://adoc.dev/storage/photos/shares/global/Dao-le-15_090718_084835.jpg
export function TopPage({ name, data }) {
	// const { data, isFetching, isLoading, error, isError } = useTopPage();
	// if (isFetching || isLoading || isError) {return null;}
	if (!data) {
		return null;
	}
	return (
		<div className="bg-white p-1 rounded-md md:grid grid-cols-3 gap-1 space-y-1 md:space-y-0">
			<div className="col-span-2">
				<PrimaryPage page={data[0]}></PrimaryPage>
			</div>
			<div className="col-span-1">
				<SecondPages pages={data.slice(1, 3)}></SecondPages>
			</div>
		</div>
	)
}

const PrimaryPage = ({ page }) => {
	return (
		<Link className="relative flex h-full" prefetch cacheFor="4m" style={{ backgroundImage: page.image_path }} href={route('detail', { alias: page.alias })}>
			<ImagePage source={page.image_path} className={'w-full h-auto rounded-md'}>
			</ImagePage>
			<p className="absolute bottom-10 left-2 md:left-8 line-clamp-2 font-semibold text-3xl text-orange-500 hover:text-purple-600">{page.title}</p>
		</Link>
	)
}

// https://tailwindcss.com/docs/grid-auto-rows
const SecondPages = ({ pages }) => {
	return (
		<div className="md:grid grid-cols-1 grid-rows-2 space-y-1 h-full">
			{pages.map((page, index) => {
				return (
					<Link prefetch cacheFor="2m" // @inertiajs/react 2.0 or newest
						className="bg-blue-gray-500 rounded-md relative grid-rows-1 flex justify-center items-center"
						key={`second-${index}`}
						href={route('detail', { alias: page.alias })}
					>
						<p className="absolute bottom-4 left-2 md:left-4 line-clamp-2 font-semibold text-2xl text-green-600 hover:text-white">{page.title}</p>
						<ImagePage source={page.image_path} className={'w-full h-auto rounded-md'}></ImagePage>
					</Link>
				)
			})}
		</div>
	)
}