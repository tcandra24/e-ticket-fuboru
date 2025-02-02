<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreRequest;
use App\Http\Requests\Event\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Event;
use App\Models\FormField;

use App\Services\Bitly;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('forms')->paginate(10);

        return view('admin.masters.events.index', ['events' => $events]);
    }

    public function create()
    {
        $formFields = FormField::all();
        return view('admin.masters.events.create', [ 'formFields' => $formFields ]);
    }

    public function store(StoreRequest $request)
    {
        try {
            $image = $request->file('image');
            $image->storeAs('public/images/events', $image->hashName());

            $slug = Str::slug($request->name);
            [ 'id' => $id, 'link' => $link ] = Bitly::createShortLink([
                'link' => url('/') . '/link/' . $slug,
                'title' => ucwords(strtoupper($request->name)),
            ]);

            $event = Event::create([
                'name'          => $request->name,
                'description'   => $request->description,
                'image'         => $image->hashName(),
                'link'          => $request->link,
                'short_link'    => $link,
                'bitly_id'      => $id,
                'slug'          => $slug,
                'model_path'    => $request->model_path,
                'date'          => $request->date,
            ]);

            $event->forms()->sync($request->fields);

            return redirect()->route('events.index')->with('success', 'Event Berhasil Disimpan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function edit(Event $event)
    {
        try {
            $formFields = FormField::all();

            return view('admin.masters.events.edit', ['event' => $event, 'formFields' => $formFields]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, Event $event)
    {
        try {
            $isActive = $request->is_active ? true : false;
            $arrayRequest = [
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $isActive,
                'link' => $request->link,
                'model_path' => $request->model_path,
                'date' => $request->date,
            ];

            if($request->file('image')) {
                if(Storage::disk('local')->exists('public/images/events/'. basename($event->name))){
                    Storage::disk('local')->delete('public/images/events/'. basename($event->image));
                }

                $image = $request->file('image');
                $image->storeAs('public/images/events', $image->hashName());

                $arrayRequest['image'] = $image->hashName();

            }

            if($event->name !== $request->name){
                $slug = Str::slug($request->name);

                if($event->bitly_id){
                    Bitly::deleteShortLink($event->bitly_id);
                }

                [ 'id' => $id, 'link' => $link ] = Bitly::createShortLink([
                    'link' => url('/') . '/link/' . $slug,
                    'title' => ucwords(strtoupper($request->name)),
                ]);

                $arrayRequest['short_link'] = $link;
                $arrayRequest['bitly_id'] = $id;
                $arrayRequest['slug'] = $slug;
            }

            $event->update($arrayRequest);

            $event->forms()->sync($request->fields);

            return redirect()->route('events.index')->with('success', 'Event Berhasil Diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->forms()->detach();
            $event->delete();

            if($event->bitly_id){
                Bitly::deleteShortLink($event->bitly_id);
            }

            if(Storage::disk('local')->exists('public/images/events/'. basename($event->name))){
                Storage::disk('local')->delete('public/images/events/'. basename($event->image));
            }

            return redirect()->route('events.index')->with('success', 'Event Berhasil Dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
