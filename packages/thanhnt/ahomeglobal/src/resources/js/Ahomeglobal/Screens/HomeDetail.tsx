
import { FunctionComponent, } from 'react';
import { HomeDetail as HomeDetailType } from '../types/Home.d';
import { RoomDetail, } from '../types/Room';
import { Head, router } from '@inertiajs/react';
import { HomeIcon } from '@heroicons/react/24/solid';
import Urls from '../netWork/Urls';
import { usePageMessage } from '../hooks';
import { RoomItem, RoomTime } from '../Components/Room';
import FlashMessage from '../Components/FlashMessage';
import Location from '../Components/Location';

type Props = {
	homeDetail: HomeDetailType,
	roomSelected: RoomDetail,
}
const HomeDetail: FunctionComponent<Props> = ({ homeDetail, }) => {
	const messages = usePageMessage();
	const homeDisable = homeDetail.order_times.map(t => {
		return t?.room_ids.length === homeDetail?.rooms.length ? t.date : undefined;
	}).filter(item => item !== undefined);

	return (
		<>
			<Head>
				<title>{homeDetail.name}</title>
			</Head>
			<div className='p-4 bg-blue-gray-100 rounded-md min-h-screen space-y-2 py-4'>
				<div className='bg-white p-4 flex space-x-3 rounded-md items-center ' onClick={() => {
					router.get(Urls.homeList);
				}}>
					<HomeIcon className='font-semibold text-blue-400 text-2xl size-8'></HomeIcon>
					<h2 className='font-semibold text-blue-400 text-2xl'>Home List redirect</h2>
				</div>
				<p className='text-2xl font-bold text-black'>Hotel: {homeDetail.name}</p>
				<FlashMessage message={messages}></FlashMessage>
				{homeDetail.rooms.length ?
					<div className='space-y-2'>
						<div className='border border-gray-700 p-1 rounded-md space-y-2'>
							<p className='text-xl font-semibold '>List rooms of the hotel:</p>
							<div className='grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4'>
								{homeDetail.rooms.map(room => <RoomItem room={room} key={room.id.toString()}></RoomItem>)}
							</div>
						</div>
						<RoomTime allDisable={homeDisable}></RoomTime>
					</div> : (
						<div className='bg-white flex justify-center items-center rounded-md p-1 lg:p-4'>
							<p className='font-semibold text-xl text-red-500 italic'>the hotel not active!</p>
						</div>
					)}
				{/* <div className='grid grid-cols-1 lg:grid-cols-2'>
					<div className='col-span-1 lg:visible'></div>
					<Location 
					style='w-[420px] h-[360px] border p-1'
					url={'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29803.393747998427!2d105.7730197906494!3d20.975625585577415!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab940344fcd5%3A0x9a83404ac3731068!2zVmnhu4duIEtp4buDbSBTw6F0IE5ow6JuIETDom4gVGjDoG5oIFBo4buRIEjDoCBO4buZaQ!5e0!3m2!1svi!2s!4v1765343379188!5m2!1svi!2s'}></Location>
				</div> */}
			</div></>
	);
}

export default HomeDetail;