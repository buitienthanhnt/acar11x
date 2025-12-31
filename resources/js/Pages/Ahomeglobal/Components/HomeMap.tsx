import { AdvancedMarker, APIProvider, Map, } from '@vis.gl/react-google-maps';
import { HomeDetail } from '../types/Home';
import { FunctionComponent, useCallback } from 'react';
import { router } from '@inertiajs/react';
import { MapPinIcon } from '@heroicons/react/24/solid';

type Props = {
	homes: HomeDetail[]
}
// https://visgl.github.io/react-google-maps/docs/api-reference/components/marker
const HomeMap: FunctionComponent<Props> = ({ homes }) => {
	const firstPlace = homes.find(home => home?.attr.find(attr => attr.key === 'g_map'))?.attr.find(attr => attr.key === 'g_map')?.value.split(",");

	if (!homes || !firstPlace) { return null; }

	return <APIProvider apiKey={''}>
		<Map mapId="DEMO_MAP_ID"
			style={{ width: '100%', height: '460px' }}
			defaultCenter={{ lat: Number(firstPlace[0]), lng: Number(firstPlace[1]) }}
			defaultZoom={12}
			gestureHandling='greedy'
			disableDefaultUI
		// zoom={12} 
		// center={{lat: 53.54992, lng: 10.00678}}
		>
			{homes.map(home => <HomePlace key={home.id} home={home}></HomePlace>)}
		</Map>
	</APIProvider>
};

const HomePlace = ({ home }: { home: HomeDetail }) => {
	const position = home?.attr.find(attr => attr.key === 'g_map')?.value.split(",");
	/**
	 * redirect to home detail
	 */
	const onClickHome = useCallback(() => {
		router.visit(route('home.detail', home.id));
	}, []);

	if (!position) {
		return;
	}

	return (
		<AdvancedMarker position={{ lat: Number(position[0]), lng: Number(position[1]) }} onClick={onClickHome}>
			<div className='relative'>
				<MapPinIcon className='w-12 h-12' color='black'></MapPinIcon>
				<div className='absolute text-sm font-semibold w-40 justify-center items-center left-1/2 -translate-x-1/2'>
					<span className='text-base font-semibold'>{home.name}</span>
				</div>
			</div>
		</AdvancedMarker>
	)
}

export default HomeMap;