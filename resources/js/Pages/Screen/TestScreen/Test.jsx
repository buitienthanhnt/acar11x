import { Link, router } from "@inertiajs/react";
import SingleLayout from '@/Layouts/BuildLayout/SingleLayout';


const Test = () => {
	const toMergeScreen = ()=>{
		router.get('/test/merge-prop',);
	}

	return(
		<div className="bg-white p-2 gap-2 flex rounded-md">
			<span className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" 
				onClick={toMergeScreen}>test merge prop
			</span>

			<Link className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" 
				href={'/test/partial-reloads'}>test partial reload prop
			</Link>

			<Link className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" 
				href={'/test/remenber-search'}>Search use remenber state
			</Link>
		</div>
	)
}

Test.layout = page => <SingleLayout children={page}></SingleLayout>

export default Test;