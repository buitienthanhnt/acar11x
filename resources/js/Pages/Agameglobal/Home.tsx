
import { Head, Link } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";

export default function Home() {

  return (
    <div className="flex flex-1 p-4 bg-blue-gray-300 flex-col gap-2 container mx-auto">
      <Head title="Agame home" />
      <h3>
        home for test agame global.
      </h3>
     <Button variant="filled">
       <Link href={route('agameglobal.dem.nguoc')} className="text-blue-500">
        Go to Countdown
      </Link>
      </Button>

      <Button variant="outline" >
         <Link href={route('agameglobal.bam.gio.don')} className="text-blue-500">
        Go to Bam gio
      </Link>
      </Button>
    </div>
  )
}