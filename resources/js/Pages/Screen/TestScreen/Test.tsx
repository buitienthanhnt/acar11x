import { Link, router } from "@inertiajs/react";
import SingleLayout from '@/Layouts/BuildLayout/SingleLayout';
import { Button } from "@material-tailwind/react";

const Test = () => {
  const toMergeScreen = () => {
    router.get('/test/merge-prop',);
  }

  return (
    <div className="bg-white p-2 gap-2 flex rounded-md">
      <Link href={'/ahome/homes'} className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">
        Ahome global
      </Link>
      <Link href={'/abook/test'} className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">
        Abook global
      </Link>

       <Link href={'/agame'} className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">
        agame global
      </Link>

      <span className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded"
        onClick={toMergeScreen}>test merge prop
      </span>

      <Link className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded"
        href={'/test/partial-reloads'}>test partial reload prop
      </Link>

      <Link className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded"
        href={'/test/remenber-search'}>Search use remenber state
      </Link>
      {/* @ts-ignore */}
      <Button variant="outlined" type="button" onClick={() => console.log('click')}>
        Material Tailwind Button
      </Button>
    </div>
  )
}

Test.layout = (page: React.ReactNode) => <SingleLayout children={page}></SingleLayout>

export default Test;