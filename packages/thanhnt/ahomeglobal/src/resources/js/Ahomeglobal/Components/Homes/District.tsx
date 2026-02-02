import { Deferred, Link } from "@inertiajs/react";
import usePageProps from "../../hooks/usePageProps";
import { sprintf } from "sprintf-js";
import Urls from "../../netWork/Urls";

/**
 * Component District
 *
 * This component is used to display the list of districts on the homepage.
 * It will display 8 districts with their names and numbers of hotels.
 * The component will use the grid layout to display the list of districts.
 * The component will also use the group and hover effects to scale up the images of the districts when the user hovers over them.
 *
 * @returns {JSX.Element} The JSX element representing the District component.
 */
const District = () => {
	const { propose } = usePageProps();

	return (
		<Deferred data={['propose']} fallback={<div>loading</div>}>
			<div className="grid grid-cols-12 w-full grid-rows-4 gap-1 xl:gap-2">
				{propose && propose.slice(0, 4).map((item, index) => {
					switch (index) {
						case 0:
							return (
								<Link href={sprintf(Urls.homeDistrict, [item.district])} className="col-span-8 row-span-1 " key={index + item.name}>
									<div className='relative h-full w-full overflow-hidden rounded-md group'>
										<div style={{ backgroundImage: `url('${item.image_path}')` }}
											className={`h-[124px] md:h-[200px] relative w-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
											<p className="absolute left-2 md:left-10 bottom-2 md:bottom-10">
												<h4 className="row-span-2 text-xl font-semibold text-white">{item.name}</h4>
												<span className="only-show-desktop invisible md:visible row-span-2 text-white">{item.location} khách sạn</span>
											</p>
										</div>
									</div>
								</Link>
							)
						case 1:
							return (
								<Link href={sprintf(Urls.homeDistrict, [item.district])} className="col-span-4 row-span-2" key={index + item.name}>
									<div className='relative h-full w-full overflow-hidden rounded-md group'>
										<div style={{ backgroundImage: `url('${item.image_path}')` }}
											className={`relative w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
											<p className="absolute left-2 md:left-10 bottom-2 md:bottom-10">
												<h4 className="row-span-2 font-semibold text-xl text-white">{item.name}</h4>
												<span className="row-span-2 only-show-desktop invisible md:visible text-white">{item.location} khách sạn</span>
											</p>
										</div>
									</div>
								</Link>
							)
						default:
							return (
								<Link href={sprintf(Urls.homeDistrict, [item.district])} className="col-span-4 row-span-1" key={index + item.name}>
									<div className='relative h-full w-full overflow-hidden rounded-md group'>
										<div style={{ backgroundImage: `url('${item.image_path}')` }}
											className={`relative w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
											<p className="absolute left-2 md:left-10 bottom-2 md:bottom-10">
												<h4 className="row-span-2 font-semibold text-xl text-white">{item.name}</h4>
												<span className="row-span-2 only-show-desktop invisible md:visible text-white">{item.location} khách sạn</span>
											</p>
										</div>
									</div>
								</Link>
							)
					}
				})
				}

				{propose && propose.slice(4, 8).map((item, index) => {
					switch (index) {
						case 0:
							return (
								<Link href={sprintf(Urls.homeDistrict, [item.district])} className="col-span-4 row-span-2" key={index}>
									<div className='relative h-full w-full overflow-hidden rounded-md group'>
										<div style={{ backgroundImage: `url('${item.image_path}')` }}
											className={`relative w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
											<p className="absolute left-2 md:left-10 bottom-2 md:bottom-10">
												<h4 className="row-span-2 font-semibold text-xl text-white">{item.name}</h4>
												<span className="row-span-2 only-show-desktop invisible md:visible text-white">{item.location} khách sạn</span>
											</p>
										</div>
									</div>
								</Link>
							)
						case 1:
							return (
								<Link href={sprintf(Urls.homeDistrict, [item.district])} className="col-span-8 row-span-1" key={index}>
									<div className='relative h-full w-full overflow-hidden rounded-md group'>
										<div style={{ backgroundImage: `url('${item.image_path}')` }}
											className={`relative w-full h-[124px] md:h-[200px] bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
											<p className="absolute left-2 md:left-10 bottom-2 md:bottom-10">
												<h4 className="row-span-2 font-semibold text-xl text-white">{item.name}</h4>
												<span className="row-span-2 only-show-desktop invisible md:visible text-white">{item.location} khách sạn</span>
											</p>
										</div>
									</div>
								</Link>
							)
						default:
							return (
								<Link href={sprintf(Urls.homeDistrict, [item.district])} className="col-span-4 row-span-1" key={index}>
									<div className='relative h-full w-full overflow-hidden rounded-md group'>
										<div style={{ backgroundImage: `url('${item.image_path}')` }}
											className={`relative w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-110 `}>
											<p className="absolute left-2 md:left-10 bottom-2 md:bottom-10">
												<h4 className="row-span-2 font-semibold text-xl text-white">{item.name}</h4>
												<span className="row-span-2 only-show-desktop invisible md:visible text-white">{item.location} khách sạn</span>
											</p>
										</div>
									</div>
								</Link>
							)
					}
				})
				}
			</div>
		</Deferred>
	)
}

export default District;