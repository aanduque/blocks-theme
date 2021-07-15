<?php get_header(); ?>

  <div id="main" class="max-w-md mx-auto" v-cloak @keydown.esc="close_all">

    <!-- All Screen -->
    <transition name="slide-fade">
      <div class="space-y-5 bg-gray-50" v-if="should_wait()">
        <div class="p-10  box-border h-screen flex flex-col">
          <!-- This example requires Tailwind CSS v2.0+ -->
          <button type="button" class="flex-grow relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center">
            <span class="text-3xl text-gray-600 lnr lnr-clock"></span>
            <span class="mt-2 block text-sm font-medium text-gray-600">
              <strong>{{ waiting_block.name }}</strong> <span v-if="get_setting(waiting_block.id).end">ends in {{ moment(get_setting(waiting_block.id).end, 'HH:mm').fromNow() }}</span>
              <small class="block text-gray-400">Enjoy, and come back in a while.</small>
            </span>
          </button>

          <div class="flex-shrink pt-8">
            
            <button @click.prevent="ignore_waiting.push(waiting_block.id)" type="button" class="block items-center px-4 py-2 border border-transparent text-base font-sm rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-center w-full text-center">
              <span>Skip</span>
            </button>

          </div>
        </div>
      </div>
    </transition>

    <div v-show="should_wait() == false" class="h-screen">

    <!-- This example requires Tailwind CSS v2.0+ -->
<nav class="bg-gray-800">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <div class="flex">
        <div class="-ml-2 mr-2 flex items-center hidden">
          <!-- Mobile menu button -->
          <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <!--
              Icon when menu is closed.

              Heroicon name: outline/menu

              Menu open: "hidden", Menu closed: "block"
            -->
            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!--
              Icon when menu is open.

              Heroicon name: outline/x

              Menu open: "block", Menu closed: "hidden"
            -->
            <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="flex-shrink-0 flex items-center font-mono text-white text-lg leading-none">
          Blo<strong>cks</strong>
        </div>

      </div>
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <button v-show="!show_date_selector" @click.prevent="show_date_selector = true" type="button" class="relative inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-xs rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500">
             <span class="mr-3 lnr lnr-calendar-full"></span> {{ moment(day, 'YYYYMMDD').format('MMMM Do YYYY') }}
          </button>
          <select
            v-show="show_date_selector"
            @change="show_date_selector = false"
            v-model="day"
            class="ml-3 block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
          >
            <option
              v-for="index in _.range(-3, 8)"
              :value="moment().subtract(index, 'days').format('YYYYMMDD')"
            >
              {{ moment().subtract(index, 'days').format('MMMM Do YYYY') }}
            </option>
          </select>
        </div>
        <div class="md:ml-4 md:flex-shrink-0 md:flex md:items-center">

          <!-- Profile dropdown -->
          <div class="ml-3 relative">
            <div>
              <button @click.prevent="menu_open = !menu_open" type="button" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 text-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                <span class="sr-only">Open user menu</span>
                      <!-- Heroicon name: solid/dots-vertical -->
      <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
      </svg>
              </button>
            </div>

            
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile menu, show/hide based on menu state. -->
  <div v-cloak v-show="menu_open" id="mobile-menu">
    <div class="px-4 sm:px-3">
      
        <fieldset 
          class="space-y-2 mb-4"
        >
          <legend class="sr-only">Notifications</legend>

          <!-- Actions -->
          <div 
            class="relative flex items-center rounded p-2 px-4"
             :class="show_controls ? 'bg-gray-900' : ''"
          >
            <div class="flex items-center h-5">

              <div class="bg-gray-700 border-2 rounded border-gray-800 w-5 h-5 flex flex-shrink-0 justify-center items-center focus-within:border-blue-500">
                <input v-model="show_controls" type="checkbox" class="opacity-0 absolute">
                <svg class="fill-current hidden w-3 h-3 text-gray-200 pointer-events-none" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
              </div>

            </div>
            <div class="ml-4 text-sm flex-grow">
              <label class="font-medium text-gray-200">
                Display Actions
              </label>
              <span class="block text-xs text-gray-400">
                Show the edit and remove buttons.
              </span>
            </div>
          </div>
          <!-- End Actions -->

          <!-- Descriptions -->
          <div 
            class="relative flex items-center rounded p-2 px-4"
             :class="show_descriptions ? 'bg-gray-900' : ''"
          >
            <div class="flex items-center h-5">

              <div class="bg-gray-700 border-2 rounded border-gray-800 w-5 h-5 flex flex-shrink-0 justify-center items-center focus-within:border-blue-500">
                <input v-model="show_descriptions" type="checkbox" class="opacity-0 absolute">
                <svg class="fill-current hidden w-3 h-3 text-gray-200 pointer-events-none" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
              </div>

            </div>
            <div class="ml-4 text-sm flex-grow">
              <label class="font-medium text-gray-200">
                Display Descriptions
              </label>
              <span class="block text-xs text-gray-400">
                Show todo descriptions.
              </span>
            </div>
          </div>
          <!-- End Descriptions -->

          <!-- Timeline -->
          <div 
            class="relative flex items-center rounded p-2 px-4"
             :class="show_timeline ? 'bg-gray-900' : ''"
          >
            <div class="flex items-center h-5">

              <div class="bg-gray-700 border-2 rounded border-gray-800 w-5 h-5 flex flex-shrink-0 justify-center items-center focus-within:border-blue-500">
                <input v-model="show_timeline" type="checkbox" class="opacity-0 absolute">
                <svg class="fill-current hidden w-3 h-3 text-gray-200 pointer-events-none" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
              </div>

            </div>
            <div class="ml-4 text-sm flex-grow">
              <label class="font-medium text-gray-200">
                Display Timeline
              </label>
              <span class="block text-xs text-gray-400">
                Show the timeline.
              </span>
            </div>
          </div>
          <!-- End Timeline -->

          <!-- Completed -->
          <div 
            class="relative flex items-center rounded p-2 px-4"
             :class="show_completed ? 'bg-gray-900' : ''"
          >
            <div class="flex items-center h-5">

              <div class="bg-gray-700 border-2 rounded border-gray-800 w-5 h-5 flex flex-shrink-0 justify-center items-center focus-within:border-blue-500">
                <input v-model="show_completed" type="checkbox" class="opacity-0 absolute">
                <svg class="fill-current hidden w-3 h-3 text-gray-200 pointer-events-none" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
              </div>

            </div>
            <div class="ml-4 text-sm flex-grow">
              <label class="font-medium text-gray-200">
                Display Completed
              </label>
              <span class="block text-xs text-gray-400">
                Show completed todos.
              </span>
            </div>
          </div>
          <!-- End Completed -->

        </fieldset>

    </div>
    <div class="pt-4 pb-3 border-t border-gray-700">
      <div class="flex items-center px-5 sm:px-6">
        <div class="flex-shrink-0">
          <img class="h-10 w-10 rounded-full" src="https://www.gravatar.com/avatar/791b4445684d179a71a8a2abe17fd3d6" alt="">
        </div>
        <div class="ml-3">
          <div class="text-base font-medium text-white">Tom Cook</div>
          <div class="text-sm font-medium text-gray-400">tom@example.com</div>
        </div>
        <button class="ml-auto flex-shrink-0 bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
          <span class="sr-only">View notifications</span>
          <!-- Heroicon name: outline/bell -->
          <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</nav>

  <div class="bg-gray-800">

    <div class="px-4 py-4 pb-12 bg-white rounded-t-lg" style="min-height: calc(100vh - 64px);">

    <div 
      class="flex"
      v-for="block in blocks"
    >

      <transition name="slide-fade-inverse">
        <div class="flex-shrink rounded bg-gray-50 p-2 mr-2 mb-4 flex flex-col font-mono"
          :class="get_setting(block.id).end == false ? 'justify-center' : 'justify-between'"
          v-if="show_timeline"
        >
          <div class="text-xs text-gray-500">
            {{ get_setting(block.id).start }}
          </div>
          <div class="relative text-xs font-bold p-1 bg-gray-800 text-white rounded-r -mx-1 -mr-3" v-if="should_display_now(block.id)">
            {{ moment().format('HH:mm') }}
            <div class="absolute text-2xs rounded-l font-normal p-1 px-2 bg-gray-800 text-white -ml-4 top-0 -left-2 font-sans">
              &rarr;
            </div>
          </div>
          <div class="text-xs text-gray-500" v-if="get_setting(block.id).end">
            {{ get_setting(block.id).end }}
          </div>
        </div>
      </transition>

      <div 
        class="block-container block mb-4 p-6 rounded flex-grow"
        :class="block.classes + ' bg-gray-50 border-r-8' + (is_open(block.id) || block.type === 'separator' ? ' opacity-100' : ' opacity-50')"
        
      >   

        <!-- This example requires Tailwind CSS v2.0+ -->
        <div class="relative" v-if="block.type === 'separator'">
          <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-start">
            <span class="pr-2 bg-gray-50 text-sm text-gray-500">
              {{ block.name }}
            </span>
          </div>
        </div>

        <div class="relative" v-if="block.type === 'block'">

          <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex items-center justify-between">
            <a 
              href="#" 
              class="pr-3 text-lg text-gray-900 bg-gray-50"
              :class="block.classes"
              @click.prevent="is_open(block.id) ? close(block.id) : open(block.id)"
            >
              {{ block.name }}
            </a>
            <div>
              <button v-show="is_open(block.id)" @click.prevent="open_form(block.id)" type="button" class="inline-flex items-center shadow-sm px-4 py-1.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <!-- Heroicon name: solid/plus-sm -->
                <svg class="-ml-1.5 mr-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>Add</span>
              </button>

            </div>
          </div>

        </div>

        <div class="space-y-5 mt-5"
                v-show="opened.includes(block.id)"
        v-if="!processed_todos[block.id]">
          <!-- This example requires Tailwind CSS v2.0+ -->
          <button @click.prevent="open_form(block.id)" type="button" class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <span class="text-3xl text-gray-600 lnr lnr-checkmark-circle"></span>
            <span class="mt-2 block text-sm font-medium text-gray-600">
              Your task list is empty
              <small class="block text-gray-400">click here to add a task</small>
            </span>
          </button>
        </div>

        <fieldset 
          class="space-y-5"
          v-show="opened.includes(block.id)"
        >
          <legend class="sr-only">Notifications</legend>

          <div 
            class="relative flex items-center"
            v-for="todo in processed_todos[block.id]"
            v-show="!todo.done || (show_completed && todo.done)"
          >
            <div class="flex items-center h-5">

              <div class="bg-white border-2 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 justify-center items-center focus-within:border-blue-500">
                <input :id="todo.id" v-model="todo.done" type="checkbox" class="opacity-0 absolute">
                <svg class="fill-current hidden w-3 h-3 text-gray-500 pointer-events-none" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
              </div>

            </div>
            <div class="ml-2 text-sm flex-grow" :class="todo.done ? 'line-through opacity-50' : ''">
              <label :for="todo.id" class="font-medium text-gray-700">
                {{ todo.name }}
              </label>
              <span v-show="show_descriptions" class="text-gray-500">
                {{ todo.description }}
              </span>
            </div>
            <div v-show="show_controls" class="text-gray-50 text-right">
              <a @click.prevent="edit_todo(todo.id)" class="text-gray-600 ml-2" href="#"><span class="lnr lnr-pencil"></span></a>
              <a @click.prevent="remove_todo(todo.id)" class="text-red-400 ml-2" href="#"><span class="lnr lnr-trash"></span></a>
            </div>
          </div>
        </fieldset>

    <transition name="slide-fade">
        <form
          id="add_new"
          class="mt-6"
          v-if="form && form.block === block.id && opened.includes(block.id)"
          @submit.prevent="submit_form"
        >

          <div class="relative mb-4">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center">
              <span class="bg-gray-50 px-2 text-gray-500">
                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                  <path fill="#6B7280" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </span>
            </div>
          </div>
          
          <div>
            <label for="task" class="block text-sm font-medium text-gray-700">Task</label>
            <div class="mt-1">
              <input required="required" v-model="form.name" type="text" name="task" id="task" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="My Task">
            </div>
          </div>

          <div>
            <label for="order" class="block mt-4 text-sm font-medium text-gray-700">Priority</label>
            <div class="mt-1">
              <input required="required" v-model="form.order" type="number" name="order" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="My Task">
            </div>
          </div>

          <div>
            <label for="block" class="block mt-4 text-sm font-medium text-gray-700">Block</label>
            <div class="mt-1">
                <select required="required" v-model="form.block" name="block" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="My Task">
                  <option :value="block_type.id" v-for="block_type in blocks" v-if="block_type.type === 'block'">
                    {{ block_type.name }}
                  </option>
                </select>
            </div>
          </div>
          
          <div>
            <label for="description" class="block mt-4 text-sm font-medium text-gray-700">Description</label>
            <div class="mt-1">
              <textarea v-model.lazy="form.description" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="My Task"></textarea>
            </div>
          </div>

          <div class="mt-4 flex justify-between">

            <button :disabled="!form.name" @click.prevent="submit_form" type="submit" class="block items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-center" :class="!form.name ? 'opacity-50' : ''">
              Add Task
            </button>

            <button @click.prevent="form = ''" type="submit" class="block items-center bg-white px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-500  text-center">
              Close
            </button>

          </div>

        </form> 
    </transition>

      </div>

    </div>

<transition name="slide-fade">
      <form
        class="my-4 p-6 rounded bg-gray-50"
        v-if="load_form"
      >        
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700">Import</label>
          <div class="mt-1">
            <textarea v-model.lazy="data_to_load" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="#datastring"></textarea>
          </div>
        </div>

        <button :disabled="!data_to_load" @click.prevent="load_from_form" type="submit" class="mt-4 block items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-center" :class="!data_to_load ? 'opacity-50' : ''">
          Load Forms
        </button>

      </form> 
      
      </transition>

    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="relative">
      <div class="absolute inset-0 flex items-center" aria-hidden="true">
        <div class="w-full border-t border-gray-300"></div>
      </div>
      <div class="relative flex justify-center">
        <span class="relative z-0 inline-flex shadow-sm rounded-md -space-x-px">
          <button type="button" @click.prevent="share()" class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-400 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
            <span class="sr-only">Share</span>
            <span class="lnr lnr-rocket"></span>
          </button>
          <button type="button" @click.prevent="show_timeline = !show_timeline" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-400 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" :class="show_timeline ? 'bg-gray-100' : ''">
            <span class="sr-only">Show Timeline</span>
            <span class="lnr lnr-clock"></span>
          </button>
          <button type="button" @click.prevent="load_from_api" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-400 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
            <span class="sr-only">Reload</span>
            <span class="lnr lnr-sync"></span>
          </button>
          <button type="button" @click.prevent="show_completed = !show_completed" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-400 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" :class="show_completed ? 'bg-gray-100' : ''">
            <span class="sr-only">Show Completed</span>
            <span class="lnr lnr-checkmark-circle"></span>
          </button>
          <button type="button" @click.prevent="load_form = !load_form" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-400 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" :class="load_form ? 'bg-gray-100' : ''">
            <span class="sr-only">Load Form</span>
            <span class="lnr lnr-database"></span>
          </button>
          <button type="button" @click.prevent="show_descriptions = !show_descriptions" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-400 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" :class="show_descriptions ? 'bg-gray-100' : ''">
            <span class="sr-only">Show Descriptions</span>
            <span class="lnr lnr-text-align-justify"></span>
          </button>
          <button @click.prevent="show_controls = !show_controls" type="button" class="relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-400 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" :class="show_controls ? 'bg-gray-100' : ''">
            <span class="sr-only">Delete</span>
            <span class="lnr lnr-trash"></span>
          </button>
        </span>
      </div>
    </div>

    </div>
    </div>
    </div>

  </div>


<?php get_footer(); ?>
