export default function ImageType(params) {
	return (
		<div className="w-full justify-center items-center flex p-1">
			<img src={params.content.value} alt="" className="w-full lg:w-2/3 xl:w-1/2 2xl:w-1/2 rounded-2xl object-cover" />
		</div>
	)
}