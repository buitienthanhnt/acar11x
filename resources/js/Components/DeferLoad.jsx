
import React from 'react';
import Loading from './Skeleton/Loading';
import { Deferred, usePage } from '@inertiajs/react';

const DeferLoad = ({ children, name }) => {
	const {props} = usePage();

	return (
		<Deferred data={name} fallback={() => <Loading></Loading>}>
			{props[name] && React.cloneElement(children, { data: props[name] })}
		</Deferred>
	)
}

export default DeferLoad