<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Contact;

class ContactsController extends Controller
{
    /**
     * Display a listing of Contact Customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::paginate(50);
        return view('backend.contacts.index', compact('contacts'));
    }

    /**
     * Edit a Contact
     */
    public function edit($id)
    {
        $contact = Contact::find($id);
        return view('backend.contacts.edit', compact('contact'));
    }

    /**
     * Update a Contact
     */
    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);
        $data = $request->all();

        $rlt = $contact->update($data);
        return back()->with('success','User updated successfully');
    }

    /**
     * Delete a Contact
     */
    public function destroy($id) {

        try {
            Contact::find($id)->delete();

            return response()->json([
                'success' => true,
                'action' => 'destroy'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
