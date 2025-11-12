
import { usePage, WhenVisible } from '@inertiajs/react';
import { Loading } from './Skeleton';
import React from 'react';

const WaitLoad = ({children, name})=>{
	const {props} = usePage();

	return(
		<WhenVisible data={name} fallback={() => <Loading></Loading>}>
			{props[name] && React.cloneElement(children, { data: props[name] })}
		</WhenVisible>
	)
}

export default WaitLoad;