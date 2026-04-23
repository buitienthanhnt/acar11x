import { Head, Link, router } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";

export default function Home() {

  return (
    <div className="grid grid-cols-2 flex-1 p-4 bg-blue-gray-100 flex-col gap-2 container mx-auto">
      <Head title="Agame home" />
      <Button variant="filled" onClick={() => {
        router.visit('acar/xe-vao');
      }}>
       
          Xe vào
        
      </Button>
    </div>
  )
}