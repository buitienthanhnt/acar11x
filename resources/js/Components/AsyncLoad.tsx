
import React from 'react';
import WaitLoad from '@/Components/WaitLoad';
import DeferLoad from './DeferLoad';

const AsyncLoad = ({ children, type, name, ...p }) => {
	if (type === 'option') {
		return (
			<WaitLoad name={name} {...p}>
				{children}
			</WaitLoad>
		)
	} else if (type === 'defer') {
		return <DeferLoad name={name} {...p}>
			{children}
		</DeferLoad>
	}

	return children;
}

export default AsyncLoad