
import React, { FunctionComponent } from 'react';
import { HomeDetail as HomeDetailType } from '../types/Home.d';
import { RoomItem as RoomItemType } from '../types/Room';
import { Link } from '@inertiajs/react';

const RoomItem = ({room}: {room: RoomItemType})=>{
	return(
		<Link className='p-4 py-2 bg-deep-purple-300 rounded-md' href={window.location.href} data={{room: room.id}}>
			<p className='font-semibold'>
				Room: {room.title}
			</p>
		</Link>
	)
}

type Props = {
	detail: HomeDetailType
}
const HomeDetail: FunctionComponent<Props> = ({detail}) => {

	return(
		<div className='p-4 bg-blue-gray-200 rounded-md min-h-screen'>
			<h3>home detail title</h3>
			<p className='text-xl font-bold text-black'>Home: {detail.name}</p>
			<div className='grid grid-cols-4 gap-4'>
				{detail.rooms.map(room => <RoomItem room={room} key={room.id.toString()}></RoomItem>)}
			</div>
		</div>
	);
}

export default HomeDetail;