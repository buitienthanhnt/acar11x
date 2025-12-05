import { BasePageProp } from "@/type/page";
import { usePage } from "@inertiajs/react"

const useProps = ()=>{
	const {props} = usePage() as BasePageProp;

	console.log(props);
	

	return {...props,};
}

export default useProps;