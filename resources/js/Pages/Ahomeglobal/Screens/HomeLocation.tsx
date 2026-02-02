import { HomeMap, Paginate } from "../Components";
import BodyLayout from "../Layouts/BodyLayout"
import { HomeDetail, HomeItemType } from "../types/Home";
import { HomeItem } from "./HomePage";
import { Head } from "@inertiajs/react";
import { PagePaginate } from "../types/Paginate";
import { FunctionComponent } from "react";

type Props = {
	homes: PagePaginate,
	location: string,
}
const HomeLocation: FunctionComponent<Props> = ({ homes, location }) => {
	return (
		<BodyLayout contentClass="py-1 space-y-2">
			<Head>
			</Head>
			<div className="py-2">
				<p className="text-xl font-semibold ">Địa chỉ:
					<span className="text-orange-700"> {location}</span>
					<span className="text-green-600 text-base">({homes.total} Kết quả)</span>
				</p>
			</div>
			{homes && <HomeMap homes={homes.data as unknown as HomeDetail[]}></HomeMap>}
			<div className="px-1 lg:px-0">
				<div className='grid md:grid-cols-3 lg:grid-cols-4 gap-1 md:gap-2 lg:gap-3 w-full'>
					{homes?.data.map(home => <HomeItem home={home as HomeItemType} key={home.id.toString()}></HomeItem>)}
				</div>
				<Paginate pageSize={homes.last_page} currentPage={homes.current_page}
					linkProps={{
						method: 'get',
						preserveScroll: true,
						prefetch: ['hover',], // prefecth must be use in GET request only
					}}>
				</Paginate>
			</div>
		</BodyLayout>
	)
}

export default HomeLocation;