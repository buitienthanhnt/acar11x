const Title = ({ title }: { title: string }) => {
	if (!title) {
		return null;
	}

	return (
		<div style={{
			backgroundImage: 'url(/ahome/assets/output-onlinepngtools.png)',
			backgroundRepeat: 'no-repeat',
			borderBottom: '3px solid #455a64',
			padding: 4
		}}>
			<p className='text-2xl text-white font-semibold'>{title}</p>
		</div>
	)
}

export default Title