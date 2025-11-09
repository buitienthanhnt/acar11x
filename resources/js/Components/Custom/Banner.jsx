import { Link } from "@inertiajs/react";

const Banner = (props)=>{
	const {data} = props;
	
	// https://developer.mozilla.org/en-US/docs/Web/CSS/aspect-ratio
	return(
		<Link href={route('detail', {alias: data.alias || ''})}
			className={`bg-white rounded-md justify-center items-center min-h-[100px] flex relative ${props.layout}`} onClick={props?.onClick}>
			<div className="absolute md:top-10 top-4 left-4 md:left-8 p-2 px-4 rounded-md shadow-white shadow-lg hover:scale-105" style={{backgroundColor: 'rgba(255, 255, 255, 0.27)'}}>
				<span className="text-lg text-red-700 font-bold ">{data.title}</span>
			</div>
			<img src={data.imagePath} alt="banner" 
				className="flex-1 rounded-lg object-cover h-1/2 lg:max-h-[720px] aspect-auto" style={{height: props.height}}
			/>
		</Link>
	)
}

export default Banner;